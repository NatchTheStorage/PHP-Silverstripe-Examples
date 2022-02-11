/* eslint-disable */
import React, {useState} from 'react';

export default ({data, selectedDate, setSelectedDate}) => {
    // const [selectedOption, setSelectedOption] = useState(data.data[0])
    return <div className='articleholderpage__filter-holder'>
        <label htmlFor="NewsPageDateFilter" className="articleholderpage__filter-label">Filter by date:</label>
        <div className="articleholderpage__filter-select-container">
            <select name="NewsPageDateFilter" id="NewsPageDateFilter" className="articleholderpage__filter-select"
            onChange={e=>setSelectedDate(e.target.value)}>
                {data.map(date => (
                <option key={date.Title} value={date.Value}>{date.Title}</option>
                ))}
            </select>
        </div>
    </div>
}
