import { Grid, LinearProgress, CssBaseline } from '@mui/material';
import AdapterDateFns from '@mui/lab/AdapterDateFns';
import LocalizationProvider from '@mui/lab/LocalizationProvider';
import { BrowserRouter as Router } from "react-router-dom";
import { useStoreState, useStoreActions } from "easy-peasy";
import { Header, Footer } from './layout/index';
import AppRoutes from './AppRoutes';
import { StyledAppContent, StyledAppBarSpacer, StyledAppApiLoading, StyledAppPaper, StyledContainer, StyledAppFlexDiv } from './App.styles';

export default function App() {

  const apiSpecInitFn = useStoreActions((actions: any) => actions.apiSpec.init);
  const authInit = useStoreActions((actions: any) => actions.auth.init);

  apiSpecInitFn();
  authInit();

  const token = useStoreState((state: any) => state.auth.token);
  const apiSpec = useStoreState((state: any) => state.apiSpec.spec);

  if (!apiSpec || Object.keys(apiSpec).length === 0) {
    return (
      <StyledAppApiLoading>
        <LinearProgress />
        <br />
        Loading API definition...
      </StyledAppApiLoading>
    );
  }

  return (
    <LocalizationProvider dateAdapter={AdapterDateFns}>
      <CssBaseline />
      <StyledAppFlexDiv>
        <Router>
          <Header loggedIn={!!token} />
          <StyledAppContent>
            <StyledAppBarSpacer />
            <StyledContainer maxWidth={'lg'}>
              <Grid container spacing={3}>
                <Grid item xs={12}>
                  <StyledAppPaper>
                    <AppRoutes token={token} apiSpec={apiSpec} />
                  </StyledAppPaper>
                </Grid>
                <Grid item xs={12}>
                  <Footer />
                </Grid>
              </Grid>
            </StyledContainer>
          </StyledAppContent>
        </Router>
      </StyledAppFlexDiv>
    </LocalizationProvider>
  );
}