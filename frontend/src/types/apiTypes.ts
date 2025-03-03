export interface Article {
  id: string;
  source_id: string;
  title: string;
  description: string;
  content: string;
  url: string;
  url_to_image: string;
  published_at: string;
  source: Source;
  categories: Category[];
  authors: Author[];
}

export interface Author {
  id: string;
  name: string;
}

export interface Category {
  id: string;
  name: string;
}

export interface Source {
  id: string;
  external_id: string;
  name: string;
}

export interface User {
  id: string;
  name: string;
  email: string;
  preferredAuthors: Author[];
  preferredCategories: Category[];
  preferredSources: Source[];
}

export interface LoginRequest {
  email: string;
  password: string;
}

export interface RegisterRequest {
  name: string;
  email: string;
  password: string;
  authors: string[];
  categories: string[];
  sources: string[];
}

export interface UpdateUserRequest {
  name: string;
  email: string;
  password: string;
  authors: string[];
  categories: string[];
  sources: string[];
} 