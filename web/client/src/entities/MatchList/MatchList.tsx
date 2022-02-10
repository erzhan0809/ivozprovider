import FormatListNumberedIcon from '@mui/icons-material/FormatListNumbered';
import EntityInterface from 'lib/entities/EntityInterface';
import _ from 'lib/services/translations/translate';
import defaultEntityBehavior from 'lib/entities/DefaultEntityBehavior';
import { MatchListProperties } from './MatchListProperties';

const properties: MatchListProperties = {
    'name': {
        label: _('Name'),
    },
};

const matchList: EntityInterface = {
    ...defaultEntityBehavior,
    icon: FormatListNumberedIcon,
    iden: 'MatchList',
    title: _('Match List', { count: 2 }),
    path: '/match_lists',
    properties
};

export default matchList;