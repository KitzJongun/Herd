export const FIELD_VERT = `
precision highp float;
attribute vec2 position;
attribute vec2 aLocal;
attribute float aWeight;
uniform vec2 uRes;
varying vec2 vLocal;
varying float vWeight;

void main() {
  vLocal = aLocal;
  vWeight = aWeight;
  vec2 clip = (position / uRes) * 2.0 - 1.0;
  gl_Position = vec4(clip.x, -clip.y, 0.0, 1.0);
}
`;

export const FIELD_FRAG = `
precision highp float;
varying vec2 vLocal;
varying float vWeight;

void main() {
  float d = length(vLocal);
  float a = exp(-d * d * 3.6) * vWeight;
  gl_FragColor = vec4(a, a, a, a);
}
`;

export const SCREEN_VERT = `
precision highp float;
attribute vec2 uv;
attribute vec2 position;
varying vec2 vUv;
void main() {
  vUv = uv;
  gl_Position = vec4(position, 0.0, 1.0);
}
`;

export const COMP_FRAG = `
precision highp float;
uniform sampler2D tField;
uniform vec3 uFireLow;
uniform vec3 uFireMid;
uniform vec3 uFireHigh;
uniform float uMerge;
uniform float uGlow;
uniform float uOpacity;
varying vec2 vUv;

vec3 fireRamp(float t) {
  vec3 c0 = uFireLow;   // deep red
  vec3 c1 = uFireMid;   // orange
  vec3 c2 = vec3(1.0, 0.92, 0.45); // yellow
  vec3 c3 = uFireHigh;  // white-hot
  if (t < 0.4) return mix(c0, c1, smoothstep(0.0, 0.4, t));
  if (t < 0.75) return mix(c1, c2, smoothstep(0.4, 0.75, t));
  return mix(c2, c3, smoothstep(0.75, 1.0, t));
}

void main() {
  float f = texture2D(tField, vUv).r;

  float edge = uMerge * 0.3;
  float core = smoothstep(uMerge - edge, uMerge + edge, f);
  float halo = smoothstep(uMerge * 0.12, uMerge, f);

  // subtle flicker so the flame feels alive
  float n = fract(sin(dot(vUv + f, vec2(12.9898, 78.233))) * 43758.5453);
  float flicker = 0.82 + 0.18 * n;

  vec3 col = fireRamp(clamp(f / max(uMerge * 2.2, 0.001), 0.0, 1.0));
  col *= 0.75 + 0.45 * core;

  float alpha = (core + halo * uGlow * (1.0 - core)) * uOpacity * flicker;
  if (alpha <= 0.003) discard;
  gl_FragColor = vec4(col, clamp(alpha, 0.0, 1.0));
}
`;
