import SettingsApplications from '@mui/icons-material/SettingsApplications';
import EntityInterface, { PropertiesList } from 'lib/entities/EntityInterface';
import defaultEntityBehavior from 'lib/entities/DefaultEntityBehavior';
import _ from 'lib/services/translations/translate';

const properties: PropertiesList = {

};

export const acl = {
    create: false,
    read: true,
    update: false,
    delete: false,
};

const terminal: EntityInterface = {
    ...defaultEntityBehavior,
    icon: <SettingsApplications />,
    iden: 'TerminalModel',
    title: _('Terminal model', { count: 2 }),
    path: '/terminal_models',
    toStr: (row: any) => row.name,
    properties,
    acl
};

export default terminal;