module Components
  module FooterColumn
    class FooterColumnComponent < Middleman::Extension
      helpers do
        def footer_column(&block)
          content_tag(:div, class: 'mb-6 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5', &block)
        end
      end
    end
  end
end

::Middleman::Extensions.register(:footer_column_component, Components::FooterColumn::FooterColumnComponent)
