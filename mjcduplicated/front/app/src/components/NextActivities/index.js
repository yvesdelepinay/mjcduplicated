/*
 * SHow the next days with acitivities
 */

/*
 * Npm import
 */
import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import moment from 'moment';
import 'moment/locale/fr';
/*
 * Local import
 */


/*
 * Code
 */
const NextActivities = ({ days, actions, displayNotifications }) => {
  // Change date to the day of the next Activities
  const onChange = (evt) => {
    const date = moment(evt);
    actions.changeDate(date);
  };
  let idDay = 0;
  return (
    <div
      id="nextActivities"
      className={classNames(
      { 'hide-notif': displayNotifications },
      )}
    >
      <h1>Prochaines journées actives :</h1>
      {days.map((day) => {
        idDay += 1;
        return (
          <p // eslint-disable-line
            key={idDay}
            onClick={() => onChange(day.date)}
          >
            -<span className="dayActivities">{moment(day.date).format('dddd D MMMM YYYY')} : {day.nbActivity} activité{day.nbActivity > 1 ? 's' : ''}</span>
          </p>
        );
      })}
    </div>
  );
};
NextActivities.propTypes = {
  days: PropTypes.arrayOf(PropTypes.object.isRequired).isRequired,
  displayNotifications: PropTypes.bool.isRequired,
  actions: PropTypes.objectOf(PropTypes.func.isRequired).isRequired,
};

/*
 * Export default
 */
export default NextActivities;
