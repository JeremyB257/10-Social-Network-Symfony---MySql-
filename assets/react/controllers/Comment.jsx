import React, {useState} from 'react';

const Comment = ({comments, currentuser}) => {
  const [open, setOpen] = useState(false);

  let jsonComments = JSON.parse(comments);

  return (
    <div>
      <i className="fa-solid fa-comment" onClick={() => setOpen(!open)}></i>
      {open &&
        jsonComments.map(com => (
          <div key={com.id} className="card mb-3">
            <div className="row g-0">
              <div className="col-1 ps-3 pt-2">
                <img src={'img/' + com.user.img} className="profileNavbarImg" alt="utilisateur" />
              </div>
              <div className="col-11 px-2 d-flex">
                <div className="card-body">
                  <h5 className="card-title">{com.user.name}</h5>

                  <p className="card-text">{com.content}</p>
                </div>

                <div className="card-body d-flex align-items-center justify-content-end">
                  {com.user.id == currentuser && (
                    <a className="btn" href={'/commentaire/suppression/' + com.id}>
                      <i className="fa-solid fa-trash"></i>
                    </a>
                  )}
                </div>
              </div>
            </div>
          </div>
        ))}
    </div>
  );
};

export default Comment;
