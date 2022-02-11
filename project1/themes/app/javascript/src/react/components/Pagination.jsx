import React, { useState } from 'react';

export default ({ arrayData, segmentationSize = 12, subsite = 'waikato' ,renderSegment }) => {
    const [currentPage, setCurrentPage] = useState(1);
    const totalPages = Math.ceil(arrayData.length / segmentationSize);

    const isPreviousActive = currentPage > 1;
    const isNextActive = currentPage < totalPages;

    const segmentStartIndex = (currentPage - 1) * segmentationSize;
    const segmentEndIndex = segmentStartIndex + segmentationSize;

    const currentSegment = arrayData.slice(segmentStartIndex, segmentEndIndex);

    const previousPage = () => {
        if (isPreviousActive) {
            setCurrentPage(currentPage - 1);
        }
    };

    const nextPage = () => {
        if (isNextActive) {
            setCurrentPage(currentPage + 1);
        }
    };

    if (!arrayData || arrayData.length === 0)
        return null;

    return (
        <div className="pagination">
            <div className="pagination__content">
                {renderSegment(currentSegment)}
            </div>
            <div className="pagination__controls">
                <button type="button" onClick={previousPage} className={`pagination__button pagination__previous${isPreviousActive ? ' pagination__button--active' : ''}`}>
                    <img alt="Previous" src="/themes/app/images/icon_arrow-right-grey.svg" />
                </button>
                <div className="pagination__current">{`Page ${currentPage} of ${totalPages}`}</div>
                <button type="button" onClick={nextPage} className={`pagination__button pagination__next${isNextActive ? ' pagination__button--active' : ''}`}>
                    <img alt="Next" src="/themes/app/images/icon_arrow-right-grey.svg"/>
                </button>
            </div>
        </div>
    );
};