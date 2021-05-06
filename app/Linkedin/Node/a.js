let payloadReq = {
    text: payload.included[1].eventContent.attributedBody.text,
    conversation_entityUrn: getConversationDetails(payload.included[1]['*from'])[0],
    user_entityUrn: getConversationDetails(payload.included[1]['*from'])[1],
    entityUrn: getMessageEntityUrn(payload.included[1].backendUrn),
    date: payload.included[1].createdAt,
    user_login: msg.data.login
}

if (payload.included[1].eventContent.hasOwnProperty('customContent') && payload.included[1].eventContent.customContent.hasOwnProperty('media')) {
    payloadReq.media = payload.included[1].eventContent.customContent.media;
}

if (payload.included[1].eventContent.hasOwnProperty('attachments')) {
    payloadReq.attachments = payload.included[1].eventContent.attachments;
}
