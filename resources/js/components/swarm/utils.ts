export const hexToRgb = (hex: string): [number, number, number] => {
  let h = (hex || '').replace('#', '').trim();
  if (h.length === 3)
    h = h
      .split('')
      .map(c => c + c)
      .join('');
  const n = parseInt(h || '000000', 16);
  return [((n >> 16) & 255) / 255, ((n >> 8) & 255) / 255, (n & 255) / 255];
};

export const buildPerm = () => {
  const src = new Uint8Array(256);
  for (let i = 0; i < 256; i++) src[i] = i;
  for (let i = 255; i > 0; i--) {
    const j = (Math.random() * (i + 1)) | 0;
    const t = src[i];
    src[i] = src[j];
    src[j] = t;
  }
  const perm = new Uint16Array(512);
  for (let i = 0; i < 512; i++) perm[i] = src[i & 255];
  return perm;
};

const smoothFade = (t: number): number => t * t * t * (t * (t * 6 - 15) + 10);

const gradDot = (h: number, x: number, y: number, z: number): number => {
  const u = h < 8 ? x : y;
  const v = h < 4 ? y : h === 12 || h === 14 ? x : z;
  return ((h & 1) === 0 ? u : -u) + ((h & 2) === 0 ? v : -v);
};

export const noise3 = (perm: Uint16Array, x: number, y: number, z: number): number => {
  const fx = Math.floor(x);
  const fy = Math.floor(y);
  const fz = Math.floor(z);
  const X = fx & 255;
  const Y = fy & 255;
  const Z = fz & 255;
  const rx = x - fx;
  const ry = y - fy;
  const rz = z - fz;
  const u = smoothFade(rx);
  const v = smoothFade(ry);
  const w = smoothFade(rz);

  const A = perm[X] + Y;
  const AA = perm[A & 511] + Z;
  const AB = perm[(A + 1) & 511] + Z;
  const B = perm[(X + 1) & 511] + Y;
  const BA = perm[B & 511] + Z;
  const BB = perm[(B + 1) & 511] + Z;

  const g000 = gradDot(perm[AA & 511] & 15, rx, ry, rz);
  const g100 = gradDot(perm[BA & 511] & 15, rx - 1, ry, rz);
  const g010 = gradDot(perm[AB & 511] & 15, rx, ry - 1, rz);
  const g110 = gradDot(perm[BB & 511] & 15, rx - 1, ry - 1, rz);
  const g001 = gradDot(perm[(AA + 1) & 511] & 15, rx, ry, rz - 1);
  const g101 = gradDot(perm[(BA + 1) & 511] & 15, rx - 1, ry, rz - 1);
  const g011 = gradDot(perm[(AB + 1) & 511] & 15, rx, ry - 1, rz - 1);
  const g111 = gradDot(perm[(BB + 1) & 511] & 15, rx - 1, ry - 1, rz - 1);

  const x00 = g000 + u * (g100 - g000);
  const x10 = g010 + u * (g110 - g010);
  const x01 = g001 + u * (g101 - g001);
  const x11 = g011 + u * (g111 - g011);
  const y0 = x00 + v * (x10 - x00);
  const y1 = x01 + v * (x11 - x01);
  return y0 + w * (y1 - y0);
};
