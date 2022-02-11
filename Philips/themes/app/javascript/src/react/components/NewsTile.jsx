import React from 'react';

export default ({article}) => {
    return <div className={'articlecard'}>
        <a href={article.link} className="articlecard__image-link">
        <div className="articlecard__image"
            style={{
            backgroundImage: "url(" + article.image + ")",
        }}>
            <div className={`articlecard__image-text ${article.articleClass}`}>
                {article.articleClass}
            </div>
        </div>
    </a>
        <div className="articlecard__content">
            <div className="articlecard__content-date">{article.date}</div>
            <div className="articlecard__content-title">{article.title}</div>
            <div className="articlecard__content-blurb">{article.summary}</div>
            <a href={article.link} className="articlecard__content-link">read more</a>
        </div>
    </div>
}
