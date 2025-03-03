import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import api from '../../services/api';
import { Author } from '../../types/apiTypes';

export const fetchAuthors = createAsyncThunk('authors/fetchAuthors', async () => {
  const response = await api.get('/v1/authors', { params: { per_page: 100 } });
  return response.data.data.data;
});

const authorsSlice = createSlice({
  name: 'authors',
  initialState: [] as Author[],
  reducers: {},
  extraReducers: (builder) => {
    builder.addCase(fetchAuthors.fulfilled, (state, action) => {
      return action.payload;
    });
  },
});

export default authorsSlice.reducer; 