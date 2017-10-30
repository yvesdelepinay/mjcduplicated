/*
 * Date Navigation : Date Picker + arrowDAy + displayDate
 */

/*
 * Npm import
 */
import React from 'react';
import PropTypes from 'prop-types';
import DatePicker from 'react-datepicker';
import moment from 'moment';
import 'moment/locale/fr';
/*
 * Local import
 */

/*
 * Code
 */
const Nav = ({ currentDate, actions }) => {
  // Change Date
  const onChange = (evt) => {
    actions.changeDate(evt);
  };
  // Up the date of one Day
  const up = () => {
    let newObj = {
      ...currentDate,
    };
    newObj = moment(newObj);
    newObj.add(1, 'days');
    actions.changeDate(newObj);
  };
  // Down the date of one Day
  const down = () => {
    let newObj = {
      ...currentDate,
    };
    newObj = moment(newObj);
    newObj.add(-1, 'days');
    actions.changeDate(newObj);
  };
  return (
    <div id="notebook-navigation">
      <div id="notebook-navigation-nav">
        <button onClick={down} id="left-arrow" className="nav-arrow"><i className="fa fa-arrow-circle-left " aria-hidden="true" /></button>
        <DatePicker
          dateFormat="DD/MM/YYYY"
          selected={currentDate}
          onChange={onChange}
          className="date-picker"
          locale="fr"
        />
        <button onClick={up} id="right-arrow" className="nav-arrow"><i className="fa fa-arrow-circle-right" aria-hidden="true" /></button>
      </div>
      <h2 id="date-title">{currentDate.format('dddd D MMMM YYYY')}</h2>
    </div>
  );
};

Nav.propTypes = {
  currentDate: PropTypes.object.isRequired,
  actions: PropTypes.objectOf(PropTypes.func.isRequired).isRequired,
};

/*
 * Export default
 */
export default Nav;
