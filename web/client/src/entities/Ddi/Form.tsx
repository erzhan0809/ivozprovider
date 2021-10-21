import defaultEntityBehavior, { EntityFormProps, FieldsetGroups } from 'lib/entities/DefaultEntityBehavior';
import { useEffect, useState } from 'react';
import ExternalCallFilterSelectOptions from 'entities/ExternalCallFilter/SelectOptions';
import UserSelectOptions from 'entities/User/SelectOptions';
import IvrSelectOptions from 'entities/Ivr/SelectOptions';
import HuntGroupSelectOptions from 'entities/HuntGroup/SelectOptions';
import FaxSelectOptions from 'entities/Fax/SelectOptions';
import ConferenceRoomSelectOptions from 'entities/ConferenceRoom/SelectOptions';
import ResidentialDeviceSelectOptions from 'entities/ResidentialDevice/SelectOptions';
import DdiProviderSelectOptions from 'entities/DdiProvider/SelectOptions';
import CountrySelectOptions from 'entities/Country/SelectOptions';
import LanguageSelectOptions from 'entities/Language/SelectOptions';
import QueueSelectOptions from 'entities/Queue/SelectOptions';
import ConditionalRouteSelectOptions from 'entities/ConditionalRoute/SelectOptions';
import RetailAccountSelectOptions from 'entities/RetailAccount/SelectOptions';
import _ from 'lib/services/translations/translate';

const Form = (props: EntityFormProps): JSX.Element => {

    const DefaultEntityForm = defaultEntityBehavior.Form;

    const [fkChoices, setFkChoices] = useState<any>({});
    const [mounted, setMounted] = useState<boolean>(true);
    const [loadingFks, setLoadingFks] = useState<boolean>(true);

    useEffect(
        () => {

            if (mounted && loadingFks) {

                ExternalCallFilterSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            externalCallFilter: options
                        }
                    });
                });

                UserSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            user: options
                        }
                    });
                });

                IvrSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            ivr: options
                        }
                    });
                });

                HuntGroupSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            huntGroup: options
                        }
                    });
                });

                FaxSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            fax: options
                        }
                    });
                });

                ConferenceRoomSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            conferenceRoom: options
                        }
                    });
                });

                ResidentialDeviceSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            residentialDevice: options
                        }
                    });
                });

                DdiProviderSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            ddiProvider: options
                        }
                    });
                });

                CountrySelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            country: options
                        }
                    });
                });

                LanguageSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            language: options
                        }
                    });
                });

                QueueSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            queue: options
                        }
                    });
                });

                ConditionalRouteSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            conditionalRoute: options
                        }
                    });
                });

                RetailAccountSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            retailAccount: options
                        }
                    });
                });

                setLoadingFks(false);
            }

            return function umount() {
                setMounted(false);
            };
        },
        [mounted, loadingFks, fkChoices]
    );

    const groups: Array<FieldsetGroups> = [
        {
            legend: _('Number data'),
            fields: [
                'country',
                'ddi',
                'ddiProvider',
                'displayName',
                'language',
            ]
        },
        {
            legend: _('Filters data'),
            fields: [
                'externalCallFilter',
            ]
        },
        {
            legend: _('Routing configuration'),
            fields: [
                'routeType',
                'user',
                'fax',
                'ivr',
                'huntGroup',
                'conferenceRoom',
                'friendValue',
                'queue',
                'residentialDevice',
                'conditionalRoute',
                'retailAccount',
            ]
        },
        {
            legend: _('Recording data'),
            fields: [
                'recordCalls',
            ]
        },
    ];

    return (<DefaultEntityForm {...props} fkChoices={fkChoices} groups={groups} />);
}

export default Form;