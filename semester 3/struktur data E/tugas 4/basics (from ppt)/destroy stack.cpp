loop (stack.top not null)
    temp = stack.top
    stack.top = stack.top->next
    redcycle (temp)
end loop

stack.count = 0
return