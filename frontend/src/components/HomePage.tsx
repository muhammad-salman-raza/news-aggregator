import React, { useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { fetchArticles } from '../store/slices/articlesSlice';
import { RootState, AppDispatch } from '../store';
import NewsItem from './NewsItem';

const HomePage = () => {
  const dispatch = useDispatch<AppDispatch>();
  const { articles, filters } = useSelector((state: RootState) => state.articles);

  useEffect(() => {
    if (!filters.categories.length) {
      dispatch(fetchArticles({ page: 1, sources: filters.sources, authors: filters.authors }));
    }
  }, [dispatch, filters.sources, filters.authors, filters.categories]);

  return (
    <div className="home-page">
      <h1>Welcome to the News Aggregator</h1>
      <NewsItem />
    </div>
  );
};

export default HomePage; 