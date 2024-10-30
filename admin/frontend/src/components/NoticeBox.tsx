import React, { useEffect } from 'react';

interface NoticeBoxProps {
    isVisible: boolean;
    onClose: () => void;
    children: React.ReactNode;
    noticeClass: string;
}

const NoticeBox: React.FC<NoticeBoxProps> = ({ noticeClass, isVisible, onClose, children }) => {
    useEffect(() => {
        let timeout: NodeJS.Timeout;
        if (isVisible) {
            timeout = setTimeout(() => {
                onClose();
            }, 5000);
        }

        return () => clearTimeout(timeout);
    }, [isVisible, onClose]);

    return <div className={`notice ${noticeClass} ${!isVisible ? 'hidden' : ''}`}>{children}</div>;
};

export default NoticeBox;