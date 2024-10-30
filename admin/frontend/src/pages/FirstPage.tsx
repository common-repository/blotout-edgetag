import React from 'react';
import { assetsUrl } from '../globals';

type PageName = 'firstPage' | 'onBoarding' | 'settingsPage';

interface FirstPageProps {
    onNavigate: (page: PageName) => void;
}

const FirstPage: React.FC<FirstPageProps> = ({ onNavigate }) => {
    const handleButtonClick = (pageName: PageName) => {
        onNavigate(pageName);
    };

    return (
        <div className='first-page'>
            <div className="first-page-content">
                <div>
                    <div className="logo">
                        <img src={`${assetsUrl}/edgetag_logo.svg`} alt="EdgeTag Logo" />
                    </div>
                    <div className="title">
                        <h1 className="text-primary">
                            <span className="title-light">Welcome to</span> EdgeTag
                        </h1>
                    </div>
                    <div className="boxes">
                        <div onClick={() => handleButtonClick('settingsPage')} className="shadow-box clickable">
                            <div>
                                <img src={`${assetsUrl}/login.svg`} alt="Login" />
                            </div>
                            <span>I already have an account</span>
                        </div>
                        <div onClick={() => handleButtonClick('onBoarding')} className="shadow-box clickable">
                            <div>
                                <img src={`${assetsUrl}/register.svg`} alt="Register" />
                            </div>
                            <span>I don't have an account yet</span>
                        </div>
                    </div>
                </div>
            </div>
            <div className="bottom-logo">
                <img src={`${assetsUrl}/blotout_logo.svg`} alt="Blotout Logo" />
            </div>
        </div>
    );
};

export default FirstPage;
