import React, { CSSProperties, ReactNode } from 'react';

interface TextBoxProps {
  children: ReactNode;
  style?: CSSProperties;
}

const TextBox: React.FC<TextBoxProps> = ({ children, style, ...props }) => {
  const containerStyle: CSSProperties = {
    backgroundColor: '#fff',
    boxShadow: '0px 10px 15px -3px rgba(0, 0, 0, 0.1), 0px 4px 6px -2px rgba(0, 0, 0, 0.05)',
    borderRadius: '10px',
    padding: '32px',
    marginBottom: '2em',
    ...style,
  };

  return (
    <div style={containerStyle} {...props}>
      {children}
    </div>
  );
};

export default TextBox;
