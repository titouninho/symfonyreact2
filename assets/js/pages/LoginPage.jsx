import React, {useState, useContext} from 'react';
import Field from '../components/forms/Field';
import AuthContext from '../contexts/AuthContext';
import AuthAPI from '../services/authAPI';

const LoginPage = ({ history}) => {

    const { setIsAuthenticated } = useContext(AuthContext);

    const [credentials, setCredentials] = useState({
        username:"",
        password:""
    });

    const [error, setError] = useState("");
    //Gestion des champs
    const handleChange = ({currentTarget}) =>{
        const {value, name} = currentTarget;
       setCredentials({...credentials, [name]: value})
    }
    //Gestion du submit
    const handleSubmit = async event => {
        event.preventDefault();
        try{
         await AuthAPI.authenticate(credentials);
         setError("")
         setIsAuthenticated(true);
         history.replace("/customers");
        }catch{
            setError("Aucun compte ne possède cette adresse email ou alors les informations ne correspondent pas !");
        }

    };

    return ( <>
        <h1>Connexion à l'application</h1>

        <form onSubmit={handleSubmit}>
        <Field label="Adresse email" name="username" value={credentials.username} onChange={handleChange} placeholder="Adresse email de connexion" error={error} />
        <Field name="password" label="Mot de passe" value={credentials.password} onChange={handleChange} type="password" error="" />
            <div className="form-group"><button type="submit" className="btn btn-success">Connexion</button></div>
        </form>
    </>);
}
 
export default LoginPage;