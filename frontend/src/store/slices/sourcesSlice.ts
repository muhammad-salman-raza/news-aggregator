import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import api from '../../services/api';
import { Source } from '../../types/apiTypes';

export const fetchSources = createAsyncThunk('sources/fetchSources', async () => {
  const response = await api.get('/v1/sources', { params: { per_page: 100 } });
  return response.data.data.data;
});

const sourcesSlice = createSlice({
  name: 'sources',
  initialState: [] as Source[],
  reducers: {},
  extraReducers: (builder) => {
    builder.addCase(fetchSources.fulfilled, (state, action) => {
      return action.payload;
    });
  },
});

export default sourcesSlice.reducer; 