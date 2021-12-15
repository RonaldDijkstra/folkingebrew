module Components
  module BeerProperty
    class BeerPropertyComponent < Middleman::Extension
      helpers do
        def beer_property(&block)
          classes = 'content-end items-end flex flex-grow flex-wrap font-bold
                     p-3 border-dotted border-r-4 border-b-4 border-l-0 lg:w-1/3'

          content_tag(:div, class: classes, &block)
        end
      end
    end
  end
end

::Middleman::Extensions.register(:beer_property_component, Components::BeerProperty::BeerPropertyComponent)
