/* eslint-disable */
import React from 'react';

export default ({data, selectedType, setSelectedType}) => {
  return <div className="articleholderpage__filters-buttons">
      {/*setSelectedMissionType*/}
    <div className="articleholderpage__filters-buttoncontainer" onChange={e=>setSelectedType(e.target.value)}>
        {data.map(typer => (
            <div key={typer.Title} className="articleholderpage__filters-button" id={typer.Title + 'Container'} onClick={e=>setSelectedType(e.target.value)}>
                {typer.Title}
                <input name="type" className="articleholderpage__filters-check" type="radio" value={typer.Value} id={typer.Title}/>
            </div>
        ))}
    </div>
  </div>
};


