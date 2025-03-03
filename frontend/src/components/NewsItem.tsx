import React, { useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { fetchArticles } from '../store/slices/articlesSlice';
import { fetchSources } from '../store/slices/sourcesSlice';
import { fetchAuthors } from '../store/slices/authorsSlice';
import { RootState, AppDispatch } from '../store';
import './NewsItem.css';
import { useParams, useNavigate } from 'react-router-dom';
import { Source, Author } from '../types/apiTypes';
import { FetchArticlesResponse } from '../store/slices/articlesSlice';

const NewsItem = () => {
  const dispatch = useDispatch<AppDispatch>();
  const articles = useSelector((state: RootState) => state.articles.articles);
  const sources = useSelector((state: RootState) => state.sources);
  const authors = useSelector((state: RootState) => state.authors);
  const { categoryId, pageNumber } = useParams<{ categoryId?: string; pageNumber?: string }>();
  const navigate = useNavigate();
  const [currentPage, setCurrentPage] = useState(pageNumber ? parseInt(pageNumber) : 1);
  const [keyword, setKeyword] = useState('');
  const [selectedSources, setSelectedSources] = useState<string[]>([]);
  const [selectedAuthors, setSelectedAuthors] = useState<string[]>([]);
  const [selectedCategory, setSelectedCategory] = useState(categoryId || '');
  const [totalPages, setTotalPages] = useState(1);
  const [showFilters, setShowFilters] = useState(false);

  useEffect(() => {
    setCurrentPage(pageNumber ? parseInt(pageNumber) : 1);
    setSelectedCategory(categoryId || '');
  }, [categoryId, pageNumber]);

  useEffect(() => {
    const fetchAndSetArticles = async () => {
      const response = await dispatch(fetchArticles({ page: currentPage, keyword, sources: selectedSources, authors: selectedAuthors, categories: selectedCategory ? [selectedCategory] : [] })) as { payload: FetchArticlesResponse };
      const totalArticles = response.payload.total || 0; // Default to 0 if undefined
      setTotalPages(response.payload.last_page || 1); // Use last_page from API response
    };

    fetchAndSetArticles();
  }, [dispatch, currentPage, keyword, selectedSources, selectedAuthors, selectedCategory]);

  useEffect(() => {
    dispatch(fetchSources());
    dispatch(fetchAuthors());
  }, [dispatch]);

  const handleNextPage = () => {
    if (currentPage < totalPages) {
      const nextPage = currentPage + 1;
      setCurrentPage(nextPage);
      const categoryPath = selectedCategory ? `/category/${selectedCategory}` : '';
      navigate(`${categoryPath}/page/${nextPage}`);
    }
  };

  const handlePreviousPage = () => {
    if (currentPage > 1) {
      const prevPage = currentPage - 1;
      setCurrentPage(prevPage);
      const categoryPath = selectedCategory ? `/category/${selectedCategory}` : '';
      navigate(`${categoryPath}/page/${prevPage}`);
    }
  };

  const handleSourceChange = (sourceId: string) => {
    setSelectedSources((prev) =>
      prev.includes(sourceId) ? prev.filter((id) => id !== sourceId) : [...prev, sourceId]
    );
  };

  const handleAuthorChange = (authorId: string) => {
    setSelectedAuthors((prev) =>
      prev.includes(authorId) ? prev.filter((id) => id !== authorId) : [...prev, authorId]
    );
  };

  const handleCategoryChange = (categoryId: string) => {
    setSelectedCategory(categoryId);
  };

  const toggleFilters = () => {
    setShowFilters(!showFilters);
  };

  return (
    <div className="news-list">
      <button onClick={toggleFilters} className="toggle-filters">
        {showFilters ? 'Hide Filters' : 'Show Filters'}
      </button>

      {showFilters && (
        <div className="filters">
          <div className="filter-group">
            <label>Sources:</label>
            {sources && sources.map((source: Source) => (
              <div key={source.id}>
                <input
                  type="checkbox"
                  checked={selectedSources.includes(source.id)}
                  onChange={() => handleSourceChange(source.id)}
                />
                {source.name}
              </div>
            ))}
          </div>
          <div className="filter-group">
            <label>Authors:</label>
            {authors && authors.map((author: Author) => (
              <div key={author.id}>
                <input
                  type="checkbox"
                  checked={selectedAuthors.includes(author.id)}
                  onChange={() => handleAuthorChange(author.id)}
                />
                {author.name}
              </div>
            ))}
          </div>
        </div>
      )}

      {articles.map((article) => (
        <div className="news-item" key={article.id}>
          <img src={article.url_to_image || 'https://via.placeholder.com/150'} alt={article.title} />
          <div className="news-content">
            <h2>{article.title}</h2>
            <p>{article.description}</p>
            <a href={article.url} target="_blank" rel="noopener noreferrer">Read more</a>
          </div>
        </div>
      ))}
      <div className="pagination-controls">
        <button onClick={handlePreviousPage} disabled={currentPage === 1}>Previous</button>
        <span>Page {currentPage} of {totalPages}</span>
        <button onClick={handleNextPage} disabled={currentPage === totalPages}>Next</button>
      </div>
    </div>
  );
};

export default NewsItem; 