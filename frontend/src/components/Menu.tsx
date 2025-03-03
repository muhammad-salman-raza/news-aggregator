import React, { useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { fetchCategories } from '../store/slices/categoriesSlice';
import { setCategories } from '../store/slices/articlesSlice';
import { RootState, AppDispatch } from '../store';
import { Link } from 'react-router-dom';
import './Menu.css';

const Menu = () => {
  const dispatch = useDispatch<AppDispatch>();
  const categories = useSelector((state: RootState) => state.categories);

  useEffect(() => {
    dispatch(fetchCategories());
  }, [dispatch]);

  const handleHomeClick = () => {
    dispatch(setCategories([]));
  };

  const handleCategoryClick = (categoryId: string) => {
    dispatch(setCategories([categoryId]));
  };

  return (
    <nav className="App-menu">
      <ul>
        <li><Link to="/" onClick={handleHomeClick}>Home</Link></li>
        {categories.map((category) => (
          <li key={category.id}><Link to={`/category/${category.id}`} onClick={() => handleCategoryClick(category.id)}>{category.name}</Link></li>
        ))}
      </ul>
    </nav>
  );
};

export default Menu; 