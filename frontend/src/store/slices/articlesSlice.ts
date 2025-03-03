import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import api from '../../services/api';
import { Article, Source, Author } from '../../types/apiTypes';

export interface FetchArticlesResponse {
  articles: Article[];
  last_page: number;
  total: number;
}

export const fetchArticles = createAsyncThunk<FetchArticlesResponse, { page: number; keyword?: string; sources: string[]; authors: string[]; categories?: string[] }>('articles/fetchArticles', async ({ page, keyword, sources, authors, categories }) => {
  const params: any = { page, sources, authors };
  if (keyword) params.keyword = keyword;
  if (categories && categories.every(category => category)) params.categories = categories;

  const response = await api.get('/v1/articles', { params });
  return {
    articles: response.data.data.data,
    last_page: response.data.data.last_page,
    total: response.data.data.total
  };
});

export const fetchSources = createAsyncThunk('articles/fetchSources', async () => {
  const response = await api.get('/v1/sources');
  return response.data.data;
});

export const fetchAuthors = createAsyncThunk('articles/fetchAuthors', async () => {
  const response = await api.get('/v1/authors');
  return response.data.data;
});

const articlesSlice = createSlice({
  name: 'articles',
  initialState: {
    articles: [] as Article[],
    filters: {
      keyword: '',
      sources: [] as string[],
      authors: [] as string[],
      categories: [] as string[],
    },
    sources: [] as Source[],
    authors: [] as Author[],
  },
  reducers: {
    setKeyword: (state, action) => {
      state.filters.keyword = action.payload;
    },
    setSources: (state, action) => {
      state.filters.sources = action.payload;
    },
    setAuthors: (state, action) => {
      state.filters.authors = action.payload;
    },
    setCategories: (state, action) => {
      state.filters.categories = action.payload;
    },
  },
  extraReducers: (builder) => {
    builder.addCase(fetchArticles.fulfilled, (state, action) => {
      state.articles = action.payload.articles;
    });
    builder.addCase(fetchSources.fulfilled, (state, action) => {
      state.sources = action.payload;
    });
    builder.addCase(fetchAuthors.fulfilled, (state, action) => {
      state.authors = action.payload;
    });
  },
});

export const { setKeyword, setSources, setAuthors, setCategories } = articlesSlice.actions;

export default articlesSlice.reducer; 