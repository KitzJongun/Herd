import type React from 'react';

export interface SwarmCursorProps extends React.HTMLAttributes<HTMLDivElement> {
  color?: string;
  accentColor?: string;
  fireLow?: string;
  fireMid?: string;
  fireHigh?: string;
  fire?: boolean;
  count?: number;
  size?: number;
  merge?: number;
  glow?: number;
  opacity?: number;
  spread?: number;
  separation?: number;
  speed?: number;
  wander?: number;
  trail?: number;
scatterOnClick?: boolean;
  enabled?: boolean;
  splash?: boolean;
  splashColor?: string;
  children?: React.ReactNode;
}

export type SwarmConfig = Required<
  Pick<
    SwarmCursorProps,
    | 'color'
    | 'accentColor'
    | 'fireLow'
    | 'fireMid'
    | 'fireHigh'
    | 'fire'
    | 'count'
    | 'size'
    | 'merge'
    | 'glow'
    | 'opacity'
    | 'spread'
    | 'separation'
    | 'speed'
    | 'wander'
    | 'trail'
    | 'scatterOnClick'
    | 'enabled'
    | 'splash'
    | 'splashColor'
  >
>;
