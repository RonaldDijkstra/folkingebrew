module Components
  module Newsletter
    class NewsletterComponent < Middleman::Extension
      helpers do
        def newsletter(opts = nil, &block)
          if opts
            additional_classes = opts.dig(:html, :class) ? " #{opts[:html][:class]}" : ''
            content_tag(:section, nil, class: "bg-gray-800 #{additional_classes}", &block)
          else
            content_tag(:section, nil, class: 'bg-gray-800', &block)
          end
        end
      end
    end
  end
end

::Middleman::Extensions.register(:newsletter_component, Components::Newsletter::NewsletterComponent)
