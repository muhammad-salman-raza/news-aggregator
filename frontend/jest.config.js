module.exports = {
  preset: 'ts-jest', // using the default ts-jest preset
  testEnvironment: 'jsdom',
  transform: {
    '^.+\\.(ts|tsx)$': 'ts-jest',
    '^.+\\.js$': 'babel-jest',
    '^.+\\.mjs$': 'babel-jest',
  },
  globals: {
    'ts-jest': {
      useESM: true,
    },
  },
  // Only treat .ts and .tsx as ESM here; .mjs files are automatically ESM.
  extensionsToTreatAsEsm: ['.ts', '.tsx'],
  moduleNameMapper: {
    '\\.(css|less)$': 'identity-obj-proxy',
  },
  // Tell Jest to transform axios even though it's in node_modules.
  transformIgnorePatterns: [
    "<rootDir>/node_modules/(?!axios/)"
  ],
};
