/*
 * Npm import
 */
import React from 'react';

/*
 * Local import
 */
import Nav from 'src/containers/DateNav';
import Activities from 'src/containers/Activities';
import Notifications from 'src/containers/Notifications';
import NextActivities from 'src/containers/NextActivities';

/*
 * Code
 */
const Diary = () => (
  <div id="notebook">
    <NextActivities />
    <Notifications />
    <Nav />
    <Activities />
  </div>
);

/*
 * Export default
 */
export default Diary;
