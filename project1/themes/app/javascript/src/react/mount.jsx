import React from 'react';
import ReactDOM from 'react-dom';

import NewsPage from "./components/NewsPage";

const newsMount = document.getElementById('news-mount');
if (newsMount) {
    ReactDOM.render(<NewsPage {...newsMount.dataset} />, newsMount);
}
