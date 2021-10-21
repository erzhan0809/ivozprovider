import defaultEntityBehavior, { EntityFormProps, FieldsetGroups } from 'lib/entities/DefaultEntityBehavior';
import { useEffect, useState } from 'react';
import TerminalSelectOptions from 'entities/Terminal/SelectOptions';
import CallAclSelectOptions from 'entities/CallAcl/SelectOptions';
import LocutionSelectOptions from 'entities/Locution/SelectOptions';
import OutgoingDdiRuleSelectOptions from 'entities/OutgoingDdiRule/SelectOptions';
import DdiSelectOptions from 'entities/Ddi/SelectOptions';
import TimezoneSelectOptions from 'entities/Timezone/SelectOptions';
import ExtensionSelectOptions from 'entities/Extension/SelectOptions';
import LanguageSelectOptions from 'entities/Language/SelectOptions';
import TransformationRuleSetSelectOptions from 'entities/TransformationRuleSet/SelectOptions';
import MatchListSelectOptions from 'entities/MatchList/SelectOptions';
import UserSelectOptions from './SelectOptions';
import PickUpGroupSelectOptions from 'entities/PickUpGroup/SelectOptions';
import _ from 'lib/services/translations/translate';

const Form = (props: EntityFormProps): JSX.Element => {

    const DefaultEntityForm = defaultEntityBehavior.Form;

    const [fkChoices, setFkChoices] = useState<any>({});
    const [mounted, setMounted] = useState<boolean>(true);
    const [loadingFks, setLoadingFks] = useState<boolean>(true);

    useEffect(
        () => {

            if (mounted && loadingFks) {

                UserSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            bossAssistant: options
                        }
                    });
                });

                MatchListSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            bossAssistantWhiteList: options
                        }
                    });
                });

                TransformationRuleSetSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            transformationRuleSet: options
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

                ExtensionSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            extension: options
                        }
                    });
                });

                TimezoneSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            timezone: options
                        }
                    });
                });

                DdiSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            outgoingDdi: options
                        }
                    });
                });

                OutgoingDdiRuleSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            outgoingDdiRule: options
                        }
                    });
                });

                LocutionSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            voicemailLocution: options
                        }
                    });
                });

                TerminalSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            terminal: options
                        }
                    });
                });

                CallAclSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            callAcl: options
                        }
                    });
                });

                PickUpGroupSelectOptions((options: any) => {
                    mounted && setFkChoices((fkChoices: any) => {
                        return {
                            ...fkChoices,
                            pickupGroupIds: options
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
            legend: _('Personal data'),
            fields: [
                'name',
                'language',
                'lastname',
                'email',
            ]
        },
        {
            legend: _('Geographic Configuration'),
            fields: [
                //'language',
                'timezone',
                'transformationRuleSet',
            ]
        },
        {
            legend: _('Login Info'),
            fields: [
                'active',
                'pass',
                'gsQRCode',
            ]
        },
        {
            legend: _('Boss-Assistant'),
            fields: [
                'isBoss',
                'bossAssistant',
                'bossAssistantWhiteList',
            ]
        },
        {
            legend: _('Basic Configuration'),
            fields: [
                'terminal',
                'extension',
                'outgoingDdi',
                'outgoingDdiRule',
                'callAcl',
                'doNotDisturb',
                'maxCalls',
                'externalIpCalls',
                'multiContact',
                'rejectCallMethod',
            ]
        },
        {
            legend: _('Voicemail'),
            fields: [
                'voicemailEnabled',
                'voicemailLocution',
                'voicemailSendMail',
                'voicemailAttachSound',
            ]
        },
        {
            legend: _('Group belonging'),
            fields: [
                'pickupGroupIds',
                //@TODO 'HuntGroupsRelUsers',
            ]
        }
    ];

    return (
        <DefaultEntityForm {...props} fkChoices={fkChoices} groups={groups} />
    );
}

export default Form;