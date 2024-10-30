import React, { useState, useEffect } from 'react';
import axios from 'axios';
import './App.css';

import FirstPage from './pages/FirstPage';
import SettingsPage from './pages/SettingsPage';
import OnboardingPage from './pages/OnboardingPage';
import ReactLoader from './components/ReactLoader';

declare var ajaxurl: string;
declare var wcbe_nonce: string;

interface DataItem {
    name: string;
    value: string;
}

type PageName = 'firstPage' | 'onBoarding' | 'settingsPage' | 'loader';

const App: React.FC = () => {
    const [data, setData] = useState<DataItem[] | null>(null);
    const [page, setPage] = useState<PageName>('loader');

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await axios.post<DataItem[]>(ajaxurl, {
                    nonce: wcbe_nonce,
                    action: 'get_edgetag_options'
                }, {
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                });

                if (Object.values(response.data[0])[0].length > 0){
                    setPage('settingsPage');
                } else {
                    setPage('firstPage');
                }
                
                setData(response.data);
            } catch (error) {
                console.error(error);
            }
        };

        fetchData();
    }, []);

    const handleNavigation = (newPage: PageName) => {
        setPage(newPage);
    };

    const renderPage = () => {
        switch (page) {
            case 'settingsPage':
                return <SettingsPage data={data} />;
            case 'onBoarding':
                return <OnboardingPage onNavigate={handleNavigation} />;
            case 'firstPage':
                return <FirstPage onNavigate={handleNavigation} />;
            default:
                return <ReactLoader />;
        }
    };

    return renderPage();
};

export default App;