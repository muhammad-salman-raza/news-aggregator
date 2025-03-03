import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import './App.css';
import Header from './components/Header';
import Menu from './components/Menu';
import NewsItem from './components/NewsItem';
import Footer from './components/Footer';
import HomePage from './components/HomePage';

function App() {
  return (
    <Router>
      <div className="App">
        <Header />
        <Menu />
        <main className="App-body">
          <Routes>
            <Route path="/category/:categoryId/page/:pageNumber" element={<NewsItem />} />
            <Route path="/category/:categoryId" element={<NewsItem />} />
            <Route path="/page/:pageNumber" element={<NewsItem />} />
            <Route path="/" element={<HomePage />} />
          </Routes>
        </main>
        <Footer />
      </div>
    </Router>
  );
}

export default App;
