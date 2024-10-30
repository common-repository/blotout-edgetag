import React from 'react';
import TextBox from '../components/TextBox';
import { assetsUrl } from '../globals';

type PageName = 'firstPage' | 'onBoarding' | 'settingsPage';

interface OnboardingPageProps {
    onNavigate: (page: PageName) => void;
}

const OnboardingPage: React.FC<OnboardingPageProps> = ({ onNavigate }) => {
    const openExternalLinkAndNavigate = () => {
        onNavigate('settingsPage');
    };

    return (
        <div className='onboarding-page'>
            <div className='onboarding-page-content'>
                <TextBox>
                    <h2>Let’s get started</h2>
                    <div>Our onboarding process is really simple and you will only
                        need couple of minutes to be fully setup. Here are the
                        following things that you will need during onboarding:
                    </div>
                    <div>
                        <ol>
                            <li>Access to your DNS provider</li>
                            <li>Credentials to the channels that you would like to add</li>
                        </ol>
                    </div>
                    <div>
                        Once you complete your onboarding, you will return to this app and enter a URL that we created for you.
                    </div>
                    <br />
                    <a href='https://app.edgetag.io/' target='_blank' rel="noreferrer" onClick={openExternalLinkAndNavigate} className='btn'>Start onboarding</a>
                </TextBox>
                <TextBox>
                    <div className="flex items-center space-between">
                        <div>
                            <h3 className='flex items-center'>
                                <span>
                                    <img src={`${assetsUrl}/slack.svg`} alt="Slack" />
                                </span>
                                <span>Ask a question to our community</span>
                            </h3>
                            <div>Become a part of our growing community and join lively discussions.</div>
                        </div>
                        <div>
                            <a target="_blank" rel="noreferrer" href="https://blotout-shared.slack.com/join/shared_invite/zt-nzwq4zpj-hOpfoZUs9Ar0n~fSxPBaSw#" className="btn">Join Slack</a>
                        </div>
                    </div>
                </TextBox>
            </div>
        </div>
    );
};

export default OnboardingPage;
