/* eslint-disable */
import React, {useState, useEffect} from 'react';
import NewsTile from "./NewsTile";
import DateFilter from "./DateFilter";
import TypeFilter from "./TypeFilter";
import Pagination from "./Pagination";

export default (data) => {
    const [articles] = useState(JSON.parse(data.articles).articles);

    const [filteredArticles, setFilteredArticles] = useState(articles);
    const dates = (JSON.parse(data.monthYears));
    const types = (JSON.parse(data.articleTypes));

    dates.sort((a, b) => Date.parse(b.Sortable) - Date.parse(a.Sortable))
    console.log(dates)

    const [selectedType, setSelectedType] = useState(types[0].value);
    const [selectedDate, setSelectedDate] = useState(dates[0].value);

    useEffect(() => {
        let allArticles = [...articles].sort((a, b) => Date.parse(b.date) - Date.parse(a.date));
        if (selectedDate) {
            allArticles = allArticles.filter(a => a.monthyear === selectedDate);
        }


        if (selectedType) {
            allArticles = allArticles.filter(a => a.type === selectedType);
        }

        setFilteredArticles(allArticles);
    }, [selectedDate, selectedType]);


    return <div>
        <div className="articleholderpage__filters">
            <div className="articleholderpage__filters-inner">

                <DateFilter
                    data={dates}
                    selectedDate={selectedDate}
                    setSelectedDate={setSelectedDate}
                />

                <TypeFilter
                    data={types}
                    selectedType={selectedType}
                    setSelectedType={setSelectedType}
                />

            </div>
        </div>
        <Pagination arrayData={filteredArticles} segmentationSize={12} subsite={data.subsite} renderSegment={(filteredArticles) => (
            <div className="articleholderpage__articles">
                <div className={'articleholderpage__articles-list'}>
                    {filteredArticles.map(news => (
                        <NewsTile
                            key={news.id}
                            article={news}
                        />
                    ))}
                </div>
            </div>
        )}/>
    </div>
}
