import { createI18n } from 'vue-i18n';
import messages from '@/locales';

export const useTranslation = () => {
  // flag icon : https://www.flaticon.com/packs/countrys-flags/3

  let locale = localStorage.getItem('locale');

  if (locale === null) {
    localStorage.setItem('locale', 'en');
    locale = 'en';
  }

  const i18n = createI18n({
    legacy: false,
    globalInjection: true,
    locale: locale,
    fallbackLocale: 'en',
    availableLocales: [
      {
        id: 'en',
        title: 'English',
      },
      {
        id: 'id',
        title: 'Indonesia',
      },
    ],
    messages: messages,
    fallbackWarn: false,
    missingWarn: false,
  });

  return { i18n };
};
