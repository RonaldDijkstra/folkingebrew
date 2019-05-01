# frozen_string_literal: true

# Raise exception for missing translations during build
class TestExceptionLocalizationHandler
  def call(exception, locale, key, options)
    raise exception.to_exception if exception.is_a?(I18n::MissingTranslation)
    super
  end
end
