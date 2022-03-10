import VoicemailIcon from '@mui/icons-material/Voicemail';
import EntityInterface from 'lib/entities/EntityInterface';
import _ from 'lib/services/translations/translate';
import defaultEntityBehavior from 'lib/entities/DefaultEntityBehavior';
import Form from './Form';
import { VoicemailProperties } from './VoicemailProperties';
import { EntityValues } from 'lib/services/entity/EntityService';
import selectOptions from './SelectOptions';

const properties: VoicemailProperties = {
    'enabled': {
        label: _('Enabled'),
        enum: {
            '0': _("No"),
            '1': _("Yes"),
        },
        default: '1',
    },
    'name': {
        label: _('Name'),
        required: true,
    },
    'sendMail': {
        label: _('Voicemail send mail'),
        enum: {
            '0': _("No"),
            '1': _("Yes"),
        },
        default: '1',
        visualToggle: {
            '0': {
                show: [],
                hide: ['attachSound', 'email'],
            },
            '1': {
                show: ['attachSound', 'email'],
                hide: [],
            }
        }
    },
    'email': {
        label: _('Email'),
        required: true,
    },
    'attachSound': {
        label: _('Voicemail attach sound'),
        enum: {
            '0': _("No"),
            '1': _("Yes"),
        },
        default: '1',
    },
    'locution': {
        label: _('Locution'),
        null: _("Unassigned"),
        default: '__null__',
    },
};

const columns = [
    'enabled',
    'name',
    'email',
];

const Voicemail: EntityInterface = {
    ...defaultEntityBehavior,
    icon: VoicemailIcon,
    iden: 'Voicemail',
    title: _('Voicemail', { count: 2 }),
    path: '/voicemails',
    toStr: (row: EntityValues) => (row.name as string || ''),
    properties,
    columns,
    Form,
    selectOptions: (props, customProps) => { return selectOptions(props, customProps); },
};

export default Voicemail;