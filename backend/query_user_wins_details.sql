SELECT 
    json_object(
        'id', uw.id,
        'user', json_object(
            'id', u.id,
            'name', u.name,
            'number', u.number,
            'email', u.email,
            'interested', u.interested,
            'address', u.address,
            'resume_file_name', u.resume_file_name
        ),
        'prize', json_object(
            'id', p.id,
            'label', p.label,
            'weight', p.weight,
            'price', p.price,
            'active', p.active
        ),
        'won_at', uw.created_at
    ) as user_won
FROM user_wins uw
JOIN users u ON uw.user_id = u.id
JOIN prizes p ON uw.prize_id = p.id;
