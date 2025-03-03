import { configureStore } from '@reduxjs/toolkit';
import articlesReducer from './slices/articlesSlice';
import categoriesReducer from './slices/categoriesSlice';
import sourcesReducer from './slices/sourcesSlice';
import authorsReducer from './slices/authorsSlice';

const store = configureStore({
  reducer: {
    articles: articlesReducer,
    categories: categoriesReducer,
    sources: sourcesReducer,
    authors: authorsReducer,
  },
});

export type RootState = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;
export default store; 