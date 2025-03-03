import React from 'react';
import { render, screen, fireEvent } from '@testing-library/react';
import { Provider } from 'react-redux';
import { configureStore } from '@reduxjs/toolkit';
import NewsItem from './NewsItem';
import { fetchArticles, fetchSources, fetchAuthors } from '../store/slices/articlesSlice';
import { Article, Source, Author } from '../types/apiTypes';

const initialState = {
  articles: {
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
};

const mockStore = configureStore({
  reducer: {
    articles: (state = initialState.articles) => state,
  },
  // Use default middleware setup
});

jest.mock('../store/slices/articlesSlice', () => ({
  fetchArticles: jest.fn(() => ({ type: 'articles/fetchArticles' })),
  fetchSources: jest.fn(() => ({ type: 'articles/fetchSources' })),
  fetchAuthors: jest.fn(() => ({ type: 'articles/fetchAuthors' })),
}));

const renderComponent = (state = initialState) => {
  return render(
    <Provider store={mockStore}>
      <NewsItem />
    </Provider>
  );
};

describe('NewsItem Component', () => {
  it('should render without crashing', () => {
    renderComponent();
    expect(screen.getByText('Show Filters')).toBeInTheDocument();
  });

  it('should toggle filters visibility', () => {
    renderComponent();
    const toggleButton = screen.getByText('Show Filters');
    fireEvent.click(toggleButton);
    expect(screen.getByText('Hide Filters')).toBeInTheDocument();
  });

  it('should call fetchArticles on mount', () => {
    renderComponent();
    expect(fetchArticles).toHaveBeenCalled();
  });

  it('should call fetchSources and fetchAuthors on mount', () => {
    renderComponent();
    expect(fetchSources).toHaveBeenCalled();
    expect(fetchAuthors).toHaveBeenCalled();
  });

  it('should handle pagination', () => {
    const stateWithArticles = {
      ...initialState,
      articles: {
        ...initialState.articles,
        articles: [{
          id: '1',
          source_id: '1',
          title: 'Test Article',
          description: 'Description',
          content: 'Content',
          url: '#',
          url_to_image: '',
          published_at: '',
          source: { id: '1', name: 'Source', external_id: 'source-external-id' },
          categories: [],
          authors: []
        }] as Article[],
      },
    };
    renderComponent(stateWithArticles);
    const nextButton = screen.getByText('Next');
    fireEvent.click(nextButton);
    expect(fetchArticles).toHaveBeenCalled();
  });
}); 