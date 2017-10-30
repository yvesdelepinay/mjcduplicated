/*
 * Npm import
 */
import React from 'react';
import { Route, Switch } from 'react-router-dom';

/*
 * Local import
 */
import Notebook from 'src/components/Notebook';
import Activity from 'src/containers/Activity';
/*
 * Code
 */
const App = () => (
  <Switch>
    <Route
      path="/ProjectMJC/projetMJC/web/app.php/activity/:id"
      component={Activity}
    />
    <Route
      path="/"
      component={Notebook}
    />
  </Switch>
);
/*
 * Export default
 */
export default App;
