/*
 * View of 1 activity with more details.
 */

/*
 * Npm import
 */
import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';
import classNames from 'classnames';
import axios from 'axios';

/*
 * Local import
 */
import Presence from 'src/containers/Presence';

/*
 * Code
 */
const Activity = ({ currentDate, startHour, finishHour, presenceTeacher, presenceStudent,
  student, teacher, user, speciality, activity_id: id, appreciation, actions }) => {
  // Change observation input
  const onChange = (evt) => {
    const { value } = evt.target;
    actions.changeInputObservation(value);
  };
  // Save observation in db
  const onSubmit = (evt) => {
    evt.preventDefault();
    const path = `../lesson/${id}/observation/edit`;
    const params = new URLSearchParams();
    params.append('id_activity', id);
    params.append('appreciation', appreciation);
    axios.post(path, params)
    .then((response) => {
      console.log(response);
    })
    .catch((error) => {
      console.log(error);
    });
  };
  appreciation = appreciation.replace(/&quot;/g, ''); // eslint-disable-line
  // Check type user and get his presence state
  const presenceType = user.user_role === 'ROLE_STUDENT' ? presenceStudent : presenceTeacher;
  // Check the state of the activity with the presenceState of both users
  const stateActivity = (presenceTeacher && presenceStudent);
  // Check type user and get his interlocutor
  const interlocutor = user.user_role === 'ROLE_STUDENT' ? teacher : student;
  return (
    <div id="activity-view">
      <h1 id="date-title">{currentDate.format('dddd D MMMM YYYY')}</h1>
      <h2 id="activity-title">Cours de {speciality} de {startHour} à {finishHour} avec {interlocutor}</h2>
      <div id="observation">
        <label id="observation-label" htmlFor="observation-input">Observation : </label>
        {(user.user_role === 'ROLE_TEACHER') ?
          <form id="form" onSubmit={onSubmit}>
            <textarea rows="3" onChange={onChange} placeholder="Votre observation..." defaultValue={appreciation} />
            <button type="submit" id="observation-submit"><span>Valider</span></button>
          </form>
        :
          <div id="appreciation">{appreciation}</div>
        }
      </div>
      <div id="infos-presence">
        <p>Vous êtes actuellement
          <span
            className={classNames(
              'activity-state',
              { absent: !presenceType },
              { present: presenceType },
            )}
          >
            {presenceType ? ' présent ' : ' absent '}
          </span>
          pour ce cours
        </p>
        <p>Votre
          {(user.user_role === 'ROLE_TEACHER') ?
            <span> élève est
              <span
                className={classNames(
                'activity-state',
                { absent: !presenceStudent },
                { present: presenceStudent },
              )}
              >
                {presenceStudent ? ' présent ' : ' absent'}
              </span>
            </span>
          :
            <span> professeur est
              <span
                className={classNames(
                  'activity-state',
                  { absent: !presenceTeacher },
                  { present: presenceTeacher },
                )}
              >
                {presenceTeacher ? ' présent ' : ' absent'}
              </span>
            </span>
          }

        </p>
        <p>Le cours
          <span
            className={classNames(
              'activity-state',
              { absent: !stateActivity },
              { present: stateActivity },
            )}
          >
            {stateActivity ? ' n\'est pas annulé' : ' est annulé'}
          </span>
        </p>
        <Presence
          presenceTeacher={presenceTeacher}
          presenceStudent={presenceStudent}
          stateActivity={stateActivity}
          id={id}
        />
      </div>

      <Link className="agenda-home-link" to="/ProjectMJC/projetMJC/web/app.php/" >
        Retour Agenda
      </Link>
    </div>
  );
};

Activity.propTypes = {
  currentDate: PropTypes.object.isRequired,
  startHour: PropTypes.string.isRequired,
  finishHour: PropTypes.string.isRequired,
  activity_id: PropTypes.number.isRequired,
  presenceTeacher: PropTypes.bool.isRequired,
  presenceStudent: PropTypes.bool.isRequired,
  student: PropTypes.string.isRequired,
  speciality: PropTypes.string.isRequired,
  appreciation: PropTypes.string.isRequired,
  teacher: PropTypes.string.isRequired,
  user: PropTypes.object.isRequired,
  actions: PropTypes.objectOf(PropTypes.func.isRequired).isRequired,
};

/*
 * Export default
 */
export default Activity;
