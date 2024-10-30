import React from 'react';
import TextBox from './TextBox';

const ReactLoader: React.FC = () => {
    return (
        <TextBox>
            <div className="overlay relative">
                <div className="loaderWrapper">
                    <div className="loaderSpinner" />
                </div>
            </div>
        </TextBox>
    );
}

export default ReactLoader;
