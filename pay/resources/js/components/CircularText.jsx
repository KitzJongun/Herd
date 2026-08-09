import React from 'react';

const CircularText = ({
    text = 'CIRCULAR TEXT',
    radius = 170,
    className = ''
}) => {
    const letters = Array.from(text);
    const rotateStep = 360 / letters.length;
    const id = 'circle-path-' + radius;

    return (
        <div
            className={className}
            style={{
                position: 'relative',
                width: radius * 2 + 'px',
                height: radius * 2 + 'px'
            }}
        >
            {letters.map((letter, index) => (
                <span
                    key={index}
                    style={{
                        position: 'absolute',
                        left: '50%',
                        top: '50%',
                        transform:
                            'rotate(' + (index * rotateStep) + 'deg) translateY(-' + radius + 'px)',
                        transformOrigin: '0 0'
                    }}
                >
                    {letter}
                </span>
            ))}
        </div>
    );
};

export default CircularText;
