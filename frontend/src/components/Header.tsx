import React from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { setKeyword, fetchArticles } from '../store/slices/articlesSlice';
import { useNavigate } from 'react-router-dom';
import { RootState, AppDispatch } from '../store';
import './Header.css';

const Header = () => {
  const dispatch = useDispatch<AppDispatch>();
  const navigate = useNavigate();
  const { filters } = useSelector((state: RootState) => state.articles);

  const handleSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const keyword = e.target.value;
    dispatch(setKeyword(keyword));
    dispatch(fetchArticles({ page: 1, keyword, sources: filters.sources, authors: filters.authors, categories: filters.categories }));
    navigate(`/page/1`);
  };

  return (
    <header className="App-header">
      <div className="search-container">
        <input type="text" placeholder="Search..." className="search-box" onChange={handleSearchChange} />
        <button className="auth-button">Register</button>
        <button className="auth-button">Login</button>
      </div>
    </header>
  );
};

export default Header; 