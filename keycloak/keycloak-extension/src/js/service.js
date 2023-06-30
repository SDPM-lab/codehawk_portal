import { fetch, overrideFetch } from "../rapper/index";
overrideFetch({
  prefix: "https://rap2.sdpmlab.org/backend/app/mock/18",
});

export { fetch };