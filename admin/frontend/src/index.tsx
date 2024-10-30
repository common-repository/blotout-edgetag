import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import App from './App';

if (document.querySelector('#wcbe-app')) {
    const root = ReactDOM.createRoot(
        document.getElementById('wcbe-app') as HTMLElement
    );

    root.render(
        <React.StrictMode>
            <App />
        </React.StrictMode>
    );
}