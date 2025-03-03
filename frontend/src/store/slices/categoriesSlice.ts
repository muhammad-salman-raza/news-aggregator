import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import api from '../../services/api';
import { Category } from '../../types/apiTypes';

export const fetchCategories = createAsyncThunk('categories/fetchCategories', async () => {
  const response = await api.get('/v1/categories');
  return response.data.data.data; // Access the nested data array
});

const categoriesSlice = createSlice({
  name: 'categories',
  initialState: [] as Category[],
  reducers: {},
  extraReducers: (builder) => {
    builder.addCase(fetchCategories.fulfilled, (state, action) => {
      return action.payload;
    });
  },
});

export default categoriesSlice.reducer; 