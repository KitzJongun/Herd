import React from 'react';

const EvilEyeBackground = () => {
  return (
    <div className="evil-eye-bg">
      <svg viewBox="0 0 720 720" className="h-full w-full">
        <defs>
          <radialGradient id="eye-outer" cx="50%" cy="50%" r="60%">
            <stop offset="0%" stopColor="#0f172a" />
            <stop offset="45%" stopColor="#0f172a" stopOpacity="0.0" />
            <stop offset="55%" stopColor="#22d3ee" stopOpacity="0.1" />
            <stop offset="75%" stopColor="#6366f1" stopOpacity="0.15" />
            <stop offset="100%" stopColor="#0f172a" stopOpacity="0.55" />
          </radialGradient>
          <radialGradient id="eye-middle" cx="50%" cy="50%" r="30%">
            <stop offset="0%" stopColor="#22d3ee" stopOpacity="0.95" />
            <stop offset="45%" stopColor="#6366f1" stopOpacity="0.5" />
            <stop offset="100%" stopColor="#0f172a" stopOpacity="0" />
          </radialGradient>
          <radialGradient id="eye-center" cx="50%" cy="50%" r="12%">
            <stop offset="0%" stopColor="#ec4899" stopOpacity="0.9" />
            <stop offset="100%" stopColor="#22d3ee" stopOpacity="0.15" />
          </radialGradient>
        </defs>
        <circle cx="360" cy="360" r="320" fill="url(#eye-outer)" />
        <circle cx="360" cy="360" r="210" fill="rgba(22, 163, 74, 0.05)" />
        <circle cx="360" cy="360" r="180" fill="url(#eye-middle)" className="eye-middle-ring" />
        <circle cx="360" cy="360" r="105" fill="url(#eye-center)" className="eye-center-core" />
        <g className="eye-rays">
          {Array.from({ length: 12 }).map((_, index) => (
            <rect
              key={index}
              x="358"
              y="60"
              width="4"
              height="90"
              rx="2"
              fill="rgba(34, 211, 238, 0.55)"
              transform={`rotate(${index * 30} 360 360)`}
            />
          ))}
        </g>
      </svg>
    </div>
  );
};

export default EvilEyeBackground;
