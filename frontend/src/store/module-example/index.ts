import { Module } from 'vuex';
import { RootState } from '../types';
import state, { ExampleStateInterface } from './state';
import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const exampleModule: Module<ExampleStateInterface, RootState> = {
  namespaced: true,
  actions,
  getters,
  mutations,
  state,
};

export default exampleModule;
