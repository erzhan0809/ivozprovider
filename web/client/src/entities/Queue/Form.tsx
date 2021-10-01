import defaultEntityBehavior, { FieldsetGroups } from 'lib/entities/DefaultEntityBehavior';
import { useEffect, useState } from 'react';
import LocutionSelectOptions from 'entities/Locution/SelectOptions';
import CountrySelectOptions from 'entities/Country/SelectOptions';
import ExtensionSelectOptions from 'entities/Extension/SelectOptions';
import UserSelectOptions from 'entities/User/SelectOptions';
import _ from 'lib/services/translations/translate';

const Form = (props:any) => {

    const DefaultEntityForm = defaultEntityBehavior.Form;

    const [fkChoices, setFkChoices] = useState<any>({});
    const [, setMounted] = useState<boolean>(true);
    const [loadingFks, setLoadingFks] = useState<boolean>(true);

    useEffect(
        () => {
            if (loadingFks) {
                LocutionSelectOptions((options:any) => {
                    setFkChoices((fkChoices:any) => {
                        return {
                            ...fkChoices,
                            timeoutLocution: options,
                            fullLocution: options,
                            periodicAnnounceLocution: options,
                        }
                    });
                });

                CountrySelectOptions((options:any) => {
                    setFkChoices((fkChoices:any) => {
                        return {
                            ...fkChoices,
                            timeoutNumberCountry: options,
                            fullNumberCountry: options,
                        }
                    });
                });

                ExtensionSelectOptions((options:any) => {
                    setFkChoices((fkChoices:any) => {
                        return {
                            ...fkChoices,
                            timeoutExtension: options,
                            fullExtension: options,
                        }
                    });
                });

                UserSelectOptions((options:any) => {
                    setFkChoices((fkChoices:any) => {
                        return {
                            ...fkChoices,
                            timeoutVoiceMailUser: options,
                            fullVoiceMailUser: options,
                        }
                    });
                });

                setLoadingFks(false);
            }

            return function umount() {
                setMounted(false);
            };
        },
        [loadingFks, fkChoices]
    );

    const groups:Array<FieldsetGroups> = [
        {
            legend: _('Basic Configuration'),
            fields: [
                'name',
                'weight',
                'strategy',
            ]
        },
        {
            legend: _('Announce'),
            fields: [
                'periodicAnnounceLocution',
                'periodicAnnounceFrequency',
            ]
        },
        {
            legend: _('Members configuration'),
            fields: [
                'memberCallTimeout',
                'memberCallRest',
                'preventMissedCalls',
            ]
        },
        {
            legend: _('Timeout configuration'),
            fields: [
                'maxWaitTime',
                'timeoutLocution',
                'timeoutTargetType',
                'timeoutExtension',
                'timeoutVoiceMailUser',
                'timeoutNumberCountry',
                'timeoutNumberValue',
            ]
        },
        {
            legend: _('Full Queue configuration'),
            fields: [
                'maxlen',
                'fullLocution',
                'fullTargetType',
                'fullExtension',
                'fullVoiceMailUser',
                'fullNumberCountry',
                'fullNumberValue',
            ]
        },
    ];

    return (<DefaultEntityForm fkChoices={fkChoices} groups={groups} {...props}  />);
}

export default Form;