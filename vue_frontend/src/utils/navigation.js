// 共通ナビゲーション関数
export const navigateToPublication = (id, isGuest = false) => {
  const route = isGuest 
    ? `/economic-statistics/${id}/detail-guest`
    : `/economic-statistics/${id}/detail`;
  return route;
};

export const navigateToStatistics = (id, isGuest = false) => {
  return navigateToPublication(id, isGuest);
};

// 他のナビゲーション関数も追加
export const navigateToSeminar = (id) => {
  return `/seminars/${id}`;
};

export const navigateToNews = (id) => {
  return `/news/${id}`;
};

export const navigateToContact = () => {
  return '/contact';
};

export const navigateToLogin = () => {
  return '/login';
};
