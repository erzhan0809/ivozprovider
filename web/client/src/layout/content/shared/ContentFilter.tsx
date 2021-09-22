import { useState, useEffect, ReactNode } from 'react';
import {
    withStyles
} from '@material-ui/core';
import FilterDialog from './FilterDialog';
import ContentFilterRow from './ContentFilterRow';
import _ from 'services/Translations/translate';

const styles = {
    paper: {
        border: '1px solid #d3d4d5',
        padding: '20px 25px',
        margin: '10px 0',
    },
};

export const filterTypes = [
    { value: "", label: _("None") },
    { value: "eq", label: _("Equals") },
    { value: "exact", label: _("Equals") },
    //{ value: "neq", label: _("Does not Equal") },
    { value: "start", label: _("Starts with") },
    { value: "partial", label: _("Contains") },
    { value: "end", label: _("Ends with") },
    { value: "gt", label: _("Is greater than") },
    { value: "gte", label: _("Is greater than equal") },
    { value: "lt", label: _("Is lower than") },
    { value: "lte", label: _("Is lower than equal") },
    { value: "exists", label: _("Exists") },
];

export const multSelectFilterTypes = [
    { value: "in", label: _("Is any of") },
];

export const getFilterTypeLabel = (type: string) => {
    for (const idx in filterTypes) {
        if (filterTypes[idx].value === type) {
            return filterTypes[idx].label;
        }
    }

    for (const idx in multSelectFilterTypes) {
        if (multSelectFilterTypes[idx].value === type) {
            return multSelectFilterTypes[idx].label;
        }
    }

    return '';
}

export const getFilterLabel = (filter: any) => {

    const hasLabels = filter.label !== undefined;
    const isArrayValue = Array.isArray(filter.value);

    if (!isArrayValue) {

        return hasLabels
            ? filter.label
            : filter.value;
    }

    if (!hasLabels) {
        return '[' + filter.value.join(', ') + ']';
    }

    return '[' + filter.label.join(', ') + ']';
}

export interface CriteriaFilterValue {
    value: string,
    label: ReactNode,
    type: string,
}

export interface CriteriaFilterValues {
    [name: string]: CriteriaFilterValue | null,
}

const ContentFilterMenu = function (props: any) {

    const {
        entityService,
        open,
        handleClose,
        currentFilter,
        setFilters,
        path
    } = props;

    const [loading, setLoading] = useState<boolean>(true);
    const [foreignEntities, setForeignEntities] = useState<any>({});
    const [criteria, setCriteria] = useState<CriteriaFilterValues>({ ...currentFilter });
    const columns = entityService.getColumns();
    const foreignKeyGetter = entityService.getForeignKeyGetter();

    useEffect(
        () => {
            if (loading) {
                foreignKeyGetter()
                    .then((foreignEntities: any) => {
                        setForeignEntities(foreignEntities);
                        setLoading(false);
                    });
            }

            return function umount() { };
        },
        [loading, foreignKeyGetter]
    );

    const apply = () => {
        setFilters(criteria);
        handleClose();
    };

    const setFilter = (fld: string, filterType: string, value: any, label?: any) => {

        const filterValue = filterType !== ''
            ? { type: filterType, value, label }
            : null;

        const filter: CriteriaFilterValues = {
            [fld]: filterValue
        };

        setCriteria({
            ...criteria,
            ...filter
        });
    };

    return (
        <FilterDialog
            open={open}
            handleClose={handleClose}
            apply={apply}
        >
            {!loading && Object.keys(columns).map((key: string, idx: number) => {

                const propertyFilter = entityService.getPropertyFilters(key, path);
                if (!propertyFilter.length) {
                    return null;
                }

                const propertyFilterTypes = filterTypes.filter((type: any) => {
                    return propertyFilter.includes(type.value);
                });

                if (!criteria[key]) {
                    return null;
                }

                const currentCriteria = criteria[key] as CriteriaFilterValue;

                const hasValue = criteria[key] && currentCriteria.value;
                const currentFilterType = hasValue
                    ? currentCriteria.type
                    : '';

                let currentFilterValues: any = {
                    value: '',
                    type: currentFilterType
                };

                const isArrayValue = hasValue && Array.isArray(currentCriteria.value);

                if (isArrayValue) {
                    const valueNumber = currentCriteria.value.length;
                    currentFilterValues.value = [];

                    for (let i = 0; i < valueNumber; i++) {
                        currentFilterValues.value.push({
                            id: currentCriteria.value[i],
                            label: currentCriteria.label || null
                        });
                    }
                } else if (hasValue) {
                    currentFilterValues.value = currentCriteria.value;
                }

                return (
                    <ContentFilterRow
                        values={currentFilterValues}
                        key={key}
                        rowNum={idx}
                        columnName={key}
                        columnLabel={columns[key].label}
                        setFilter={setFilter}
                        filterTypes={propertyFilterTypes}
                        choices={foreignEntities[key]}
                    />
                );
            })}
        </FilterDialog>
    );
}

export default withStyles(styles)(ContentFilterMenu);
