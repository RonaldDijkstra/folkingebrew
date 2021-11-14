module Components
  module MarkdownContent
    class MarkdownContentComponent < Middleman::Extension
      helpers do 
        def markdown_content(opts = nil, &block)
          if opts 
            additional_classes = opts.dig(:html, :class) ? " #{opts[:html][:class]}" : ""
            content_tag(:article, nil, class: "prose max-w-none #{additional_classes}", &block)
          else 
            content_tag(:article, nil, class: "prose max-w-none", &block) 
          end
        end
      end
    end
  end
end

::Middleman::Extensions.register(:markdown_content_component, Components::MarkdownContent::MarkdownContentComponent)
